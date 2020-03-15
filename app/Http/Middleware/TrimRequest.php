<?php

namespace App\Http\Middleware;

use Closure;

class TrimRequest
{
    // Trim Request Thank https://www.webniraj.com/2017/02/21/laravel-5-2-trim-all-input-using-middleware/
    // https://gist.github.com/drakakisgeo/3bba2a2600b4c554f836
    
    public function handle($request, Closure $next)
    {
        $input = $request->all();
        if ($input) {
            array_walk_recursive($input, function (&$item) {
                $item = trim($item);
                $item = ($item == "") ? null : $item;
            });
            $request->merge($input);
        }
        return $next($request);
    }
}
