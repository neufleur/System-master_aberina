namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
class AdminController extends Controller

{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
         return view('admin.users');

        } // 他の管理者専用アクション }