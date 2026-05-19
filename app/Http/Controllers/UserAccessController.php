<?php

namespace App\Http\Controllers;

use App\Models\UserAppAccess;
use App\Services\ExternalUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserAccessController extends Controller
{
    public function userAccess(Request $request, string $username)
    {
        $request->validate([
            'application' => ['nullable', Rule::in(UserAppAccess::applications())],
        ]);

        $applicationCode = $request->query('application', UserAppAccess::APPLICATION_GESTION_ROLES);

        $access = UserAppAccess::query()
            ->where('username', $username)
            ->where('application_code', $applicationCode)
            ->where('active', true)
            ->orderBy('id')
            ->get(['role', 'facultad_id', 'departamento_id', 'active']);

        return response()->json([
            'username' => $username,
            'application_code' => $applicationCode,
            'can_access' => $access->isNotEmpty(),
            'access' => $access,
        ]);
    }

    public function index(Request $request)
    {
        $data = $request->validate([
            'application' => ['nullable', Rule::in(UserAppAccess::applications())],
            'facultad_id' => ['nullable', 'integer', 'exists:facultad,id'],
        ]);

        $applicationCode = $data['application'] ?? UserAppAccess::APPLICATION_GESTION_ROLES;

        return UserAppAccess::query()
            ->where('application_code', $applicationCode)
            ->where('active', true)
            ->when(isset($data['facultad_id']), function ($query) use ($data) {
                $query->where('facultad_id', $data['facultad_id']);
            })
            ->orderBy('role')
            ->orderBy('username')
            ->get(['username', 'role', 'facultad_id', 'departamento_id', 'active']);
    }

    public function assign(Request $request)
    {
        $data = $request->validate([
            'application_code' => ['required', Rule::in(UserAppAccess::applications())],
            'username' => ['required', 'string'],
            'role' => ['required', 'string'],
            'facultad_id' => ['nullable', 'integer', 'exists:facultad,id'],
            'departamento_id' => ['nullable', 'integer', 'exists:departamento,id'],
        ]);

        if (!in_array($data['role'], UserAppAccess::rolesForApplication($data['application_code']), true)) {
            return response()->json([
                'message' => 'El rol no pertenece a la aplicación indicada.',
            ], 422);
        }

        if ($data['role'] === 'admin') {
            return response()->json([
                'message' => 'El rol admin debe asignarse desde /api/access/admin/transfer.',
            ], 422);
        }

        $userValidation = $this->validateExternalUsername($data['username']);

        if ($userValidation) {
            return $userValidation;
        }

        $roleValidation = $this->validateRoleScope($data);

        if ($roleValidation) {
            return $roleValidation;
        }

        $access = DB::transaction(function () use ($data) {
            $this->deactivateConflictingAccess($data);

            return UserAppAccess::create([
                'username' => $data['username'],
                'application_code' => $data['application_code'],
                'role' => $data['role'],
                'facultad_id' => $data['facultad_id'],
                'departamento_id' => $data['departamento_id'],
                'active' => true,
            ]);
        });

        return response()->json($this->accessPayload($access), 201);
    }

    public function transferAdmin(Request $request)
    {
        $data = $request->validate([
            'application_code' => ['required', Rule::in(UserAppAccess::applications())],
            'username' => ['required', 'string'],
        ]);

        $userValidation = $this->validateExternalUsername($data['username']);

        if ($userValidation) {
            return $userValidation;
        }

        $access = DB::transaction(function () use ($data) {
            UserAppAccess::query()
                ->where('application_code', $data['application_code'])
                ->where('role', 'admin')
                ->where('active', true)
                ->update(['active' => false]);

            return UserAppAccess::create([
                'username' => $data['username'],
                'application_code' => $data['application_code'],
                'role' => 'admin',
                'facultad_id' => null,
                'departamento_id' => null,
                'active' => true,
            ]);
        });

        return response()->json($this->accessPayload($access), 201);
    }

    private function deactivateConflictingAccess(array $data): void
    {
        $query = UserAppAccess::query()
            ->where('application_code', $data['application_code'])
            ->where('role', $data['role'])
            ->where('active', true);

        if (in_array($data['role'], ['vicedecano_docente', 'decano'], true)) {
            $query->where('facultad_id', $data['facultad_id']);
        }

        if ($data['role'] === 'jefe_departamento') {
            $query->where('departamento_id', $data['departamento_id']);
        }

        $query->update(['active' => false]);
    }

    private function validateRoleScope(array &$data)
    {
        if ($data['role'] === 'jefe_departamento') {
            if (empty($data['facultad_id']) || empty($data['departamento_id'])) {
                return response()->json([
                    'message' => 'El jefe_departamento requiere facultad_id y departamento_id.',
                ], 422);
            }

            if (!$this->departamentoPerteneceAFacultad($data['departamento_id'], $data['facultad_id'])) {
                return response()->json([
                    'message' => 'El departamento no pertenece a la facultad indicada.',
                ], 422);
            }

            return null;
        }

        if (in_array($data['role'], ['vicedecano_docente', 'decano'], true)) {
            if (empty($data['facultad_id'])) {
                return response()->json([
                    'message' => "El rol {$data['role']} requiere facultad_id.",
                ], 422);
            }

            $data['departamento_id'] = null;

            return null;
        }

        if ($data['role'] === 'rector') {
            $data['facultad_id'] = null;
            $data['departamento_id'] = null;
        }

        return null;
    }

    private function validateExternalUsername(string $username)
    {
        $exists = app(ExternalUserService::class)->usernameExists($username);

        if ($exists === true) {
            return null;
        }

        if ($exists === false) {
            return response()->json([
                'message' => 'El usuario no existe en la API de usuarios.',
            ], 422);
        }

        return response()->json([
            'message' => 'No se pudo validar el usuario en la API de usuarios.',
        ], 503);
    }

    private function departamentoPerteneceAFacultad(int $departamentoId, int $facultadId): bool
    {
        return DB::table('facultad_departamento')
            ->where('id_departamento', $departamentoId)
            ->where('id_facultad', $facultadId)
            ->exists();
    }

    private function accessPayload(UserAppAccess $access): array
    {
        return [
            'username' => $access->username,
            'application_code' => $access->application_code,
            'role' => $access->role,
            'facultad_id' => $access->facultad_id,
            'departamento_id' => $access->departamento_id,
            'active' => $access->active,
        ];
    }
}
