namespace App\Modules\Home\Services;

use App\Modules\AppPost\Models\AppPost;

class HomeService
{
    public function getLatestApps()
    {
        return AppPost::with('user')
            ->latest()
            ->get();
    }
} 