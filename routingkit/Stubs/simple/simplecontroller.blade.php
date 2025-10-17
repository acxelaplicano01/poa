{!! '<?php' !!}

namespace {{ $data['controller']['namespace'] }};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class {{ $data['controller']['className'] }} extends Controller
{
    public function index()
    {
        return view('{{ $data['view']['viewName'] }}');
    }
}
