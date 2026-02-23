<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Contact;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $type = $request->get('type', 'clients');
        $limit = min($request->get('limit', 10), 50);

        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $results = [];

        if ($type === 'clients') {
            $results = Client::where('client_name', 'like', "%{$q}%")
                ->select('id', 'client_name as name')
                ->limit($limit)
                ->get();
        } elseif ($type === 'contacts') {
            $results = Contact::where('contact_name', 'like', "%{$q}%")
                ->select('id', 'contact_name as name')
                ->limit($limit)
                ->get();
        }

        return response()->json($results);
    }
}
