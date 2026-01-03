<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class SessionManager extends Component
{
    use WithPagination;

    // Propiedades para la tabla
    public $search = '';
    public $perPage = 10;
    public $sortField = 'last_activity';
    public $sortDirection = 'desc';
    public $confirmingTerminate = false;
    public $sessionIdToTerminate;
    public $sessionUserName;
    public $viewingSessionDetails = false;
    public $sessionDetails = null;
    
    // Método para ordenar columnas
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }
    
    // Confirmar terminación de sesión
    public function confirmTerminate($sessionId, $userId = null)
    {
        $this->sessionIdToTerminate = $sessionId;
        
        // Obtener el nombre del usuario si está disponible
        if ($userId) {
            $user = User::find($userId);
            $this->sessionUserName = $user ? $user->name : 'Usuario desconocido';
        } else {
            $this->sessionUserName = 'Usuario desconocido';
        }
        
        $this->confirmingTerminate = true;
    }
    
    // Cancelar terminación
    public function cancelTerminate()
    {
        $this->confirmingTerminate = false;
        $this->sessionIdToTerminate = null;
        $this->sessionUserName = null;
    }
    
    // Terminar sesión
    public function terminateSession()
    {
        try {
            if ($this->sessionIdToTerminate) {
                DB::table('sessions')
                    ->where('id', $this->sessionIdToTerminate)
                    ->delete();
                
                session()->flash('message', 'Sesión terminada correctamente.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al terminar la sesión: ' . $e->getMessage());
        }
        
        $this->confirmingTerminate = false;
        $this->sessionIdToTerminate = null;
    }
    
    // Terminar todas las sesiones de un usuario
    public function terminateAllUserSessions($userId)
    {
        try {
            $user = User::find($userId);
            if ($user) {
                DB::table('sessions')
                    ->where('user_id', $userId)
                    ->delete();
                
                session()->flash('message', 'Todas las sesiones de ' . $user->name . ' han sido terminadas.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al terminar las sesiones: ' . $e->getMessage());
        }
    }

    // Ver detalles de la sesión
    public function viewSessionDetails($sessionId)
    {
        $session = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select(
                'sessions.id',
                'sessions.user_id',
                'sessions.ip_address',
                'sessions.user_agent',
                'sessions.last_activity',
                'users.name',
                'users.email'
            )
            ->where('sessions.id', $sessionId)
            ->first();

        if ($session) {
            $session->browser = $this->getBrowser($session->user_agent);
            $session->browser_version = $this->getBrowserVersion($session->user_agent);
            $session->platform = $this->getPlatform($session->user_agent);
            $session->platform_version = $this->getPlatformVersion($session->user_agent);
            $session->time_ago = Carbon::createFromTimestamp($session->last_activity)->diffForHumans();
            $session->is_current_device = $session->id === session()->getId();
        }

        $this->sessionDetails = $session;
        $this->viewingSessionDetails = true;
    }

    // Cerrar modal de detalles
    public function closeSessionDetailsModal()
    {
        $this->viewingSessionDetails = false;
        $this->sessionDetails = null;
    }

    // Terminar sesión desde el modal de detalles
    public function terminateSessionFromDetails()
    {
        if ($this->sessionDetails && $this->sessionDetails->id) {
            $this->sessionIdToTerminate = $this->sessionDetails->id;
            $this->terminateSession();
            $this->closeSessionDetailsModal();
        }
    }

    public function render()
    {
        // Obtener sesiones con información de usuarios
        $sessionsQuery = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select(
                'sessions.id',
                'sessions.user_id',
                'sessions.ip_address',
                'sessions.user_agent',
                'sessions.last_activity',
                'users.name',
                'users.email'
            )
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('users.name', 'like', '%' . $this->search . '%')
                      ->orWhere('users.email', 'like', '%' . $this->search . '%')
                      ->orWhere('sessions.ip_address', 'like', '%' . $this->search . '%');
                });
            });
            
        // Aplicar ordenamiento
        if ($this->sortField === 'user') {
            $sessionsQuery->orderBy('users.name', $this->sortDirection);
        } else {
            $sessionsQuery->orderBy('sessions.' . $this->sortField, $this->sortDirection);
        }
            
        $sessions = $sessionsQuery->paginate($this->perPage);
        
        // Transformar los datos para la vista
        $sessions->getCollection()->transform(function ($session) {
            $session->browser = $this->getBrowser($session->user_agent);
            $session->browser_version = $this->getBrowserVersion($session->user_agent);
            $session->platform = $this->getPlatform($session->user_agent);
            $session->platform_version = $this->getPlatformVersion($session->user_agent);
            $session->time_ago = Carbon::createFromTimestamp($session->last_activity)->diffForHumans();
            $session->is_current_device = $session->id === session()->getId();
            return $session;
        });

        return view('livewire.admin.session-manager', [
            'sessions' => $sessions
        ]);
    }
    
    // Función para detectar el navegador
    private function getBrowser($userAgent)
    {
        if (strpos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (strpos($userAgent, 'Chrome') !== false && strpos($userAgent, 'Edg') === false) {
            return 'Chrome';
        } elseif (strpos($userAgent, 'Edg') !== false) {
            return 'Edge';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            return 'Safari';
        } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
            return 'Opera';
        } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident/') !== false) {
            return 'Internet Explorer';
        } else {
            return 'Desconocido';
        }
    }
    
    // Función para obtener la versión del navegador
    private function getBrowserVersion($userAgent)
    {
        $version = null;
        
        if (strpos($userAgent, 'Firefox') !== false) {
            preg_match('/Firefox\/([0-9.]+)/', $userAgent, $matches);
            $version = $matches[1] ?? null;
        } elseif (strpos($userAgent, 'Chrome') !== false && strpos($userAgent, 'Edg') === false) {
            preg_match('/Chrome\/([0-9.]+)/', $userAgent, $matches);
            $version = $matches[1] ?? null;
        } elseif (strpos($userAgent, 'Edg') !== false) {
            preg_match('/Edg\/([0-9.]+)/', $userAgent, $matches);
            $version = $matches[1] ?? null;
        } elseif (strpos($userAgent, 'Safari') !== false) {
            preg_match('/Version\/([0-9.]+)/', $userAgent, $matches);
            $version = $matches[1] ?? null;
        } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
            preg_match('/(Opera|OPR)\/([0-9.]+)/', $userAgent, $matches);
            $version = $matches[2] ?? null;
        } elseif (strpos($userAgent, 'MSIE') !== false) {
            preg_match('/MSIE ([0-9.]+)/', $userAgent, $matches);
            $version = $matches[1] ?? null;
        } elseif (strpos($userAgent, 'Trident/') !== false) {
            $version = '11.0'; // IE 11
        }
        
        return $version;
    }
    
    // Función para detectar el sistema operativo
    private function getPlatform($userAgent)
    {
        if (strpos($userAgent, 'Windows') !== false) {
            return 'Windows';
        } elseif (strpos($userAgent, 'Macintosh') !== false) {
            return 'MacOS';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            return 'Linux';
        } elseif (strpos($userAgent, 'iPhone') !== false) {
            return 'iOS';
        } elseif (strpos($userAgent, 'Android') !== false) {
            return 'Android';
        } else {
            return 'Desconocido';
        }
    }
    
    // Función para obtener la versión del sistema operativo
    private function getPlatformVersion($userAgent)
    {
        $version = null;
        
        if (strpos($userAgent, 'Windows') !== false) {
            if (strpos($userAgent, 'Windows NT 10.0') !== false) {
                $version = '10';
            } elseif (strpos($userAgent, 'Windows NT 6.3') !== false) {
                $version = '8.1';
            } elseif (strpos($userAgent, 'Windows NT 6.2') !== false) {
                $version = '8';
            } elseif (strpos($userAgent, 'Windows NT 6.1') !== false) {
                $version = '7';
            } elseif (strpos($userAgent, 'Windows NT 6.0') !== false) {
                $version = 'Vista';
            } elseif (strpos($userAgent, 'Windows NT 5.1') !== false) {
                $version = 'XP';
            }
        } elseif (strpos($userAgent, 'Mac OS X') !== false) {
            preg_match('/Mac OS X ([0-9_\.]+)/', $userAgent, $matches);
            if (isset($matches[1])) {
                $version = str_replace('_', '.', $matches[1]);
            }
        } elseif (strpos($userAgent, 'Android') !== false) {
            preg_match('/Android ([0-9\.]+)/', $userAgent, $matches);
            $version = $matches[1] ?? null;
        } elseif (strpos($userAgent, 'iPhone OS') !== false) {
            preg_match('/iPhone OS ([0-9_]+)/', $userAgent, $matches);
            if (isset($matches[1])) {
                $version = str_replace('_', '.', $matches[1]);
            }
        }
        
        return $version;
    }
}