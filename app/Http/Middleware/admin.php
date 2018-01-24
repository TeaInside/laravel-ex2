<?php

namespace App\Http\Middleware;

use DB;
use Confide;
use Closure;
use Redirect;

class admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $a = Confide::user();
        if ($a === null) {
            return Redirect::to(route('user.login'));
        }
        $a = $a->toArray();
        $q = DB::table('users_roles')->join('roles', 'users_roles.role_id', '=', 'roles.id', 'inner')->where('users_roles.user_id', $a['id'])->first();
        if (isset($q->name) && $q->name === 'Admin') {
            return $next($request);
        } else {
            http_response_code(403);
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <script type="text/javascript">
                    alert('You are not allowed to access this page!');
                    window.location = '<?php print route('user.view_profile'); ?>';
                </script>
                <title>403 Forbidden</title>
            </head>
            </html>
            <?php
            exit();
        }
    }
}
