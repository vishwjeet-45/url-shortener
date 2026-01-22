<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;

class ShortUrlController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->hasRole('SuperAdmin')) {
            $shortUrls = ShortUrl::paginate(10);
            return view('short-urls-list', compact('shortUrls'));
        }
        if ($user->hasRole('Admin')) {
            $shortUrls = ShortUrl::where('company_id', $user->company_id)->paginate(10);
            return view('short-urls-list', compact('shortUrls'));
        } elseif ($user->hasRole('Member')) {
            $shortUrls = ShortUrl::where('user_id', $user->id)->where('company_id', $user->company_id)->paginate(10);
            return view('short-urls-list', compact('shortUrls'));
        }
        $shortUrls = auth()->user()->company->shortUrls()->paginate(10);
        return view('short-urls-list', compact('shortUrls'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('Admin') && !auth()->user()->hasRole('Member')) {
            abort(403, 'Unauthorized Action');
        }
        return view('short-url-create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('Admin') && !auth()->user()->hasRole('Member')) {
            abort(403, 'Unauthorized Action');
        }
        $validated = $request->validate([
            'original_url' => 'required|url|max:2048',
        ]);

        ShortUrl::create([
            'original_url' => $validated['original_url'],
            'short_code' => substr(md5(uniqid(rand(), true)), 0, 6),
            'user_id' => auth()->user()->id,
            'company_id' => auth()->user()->company_id,
        ]);

        return redirect()->route('short-urls.index')->with('success', 'Short URL created successfully.');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasRole('Admin') && !auth()->user()->hasRole('Member')) {
            abort(403, 'Unauthorized Action');
        }
        $shortUrl = ShortUrl::findOrFail($id);
        if (auth()->user()->hasRole('Member') && $shortUrl->user_id != auth()->user()->id) {
            abort(403, 'Unauthorized Action');
        }
        $shortUrl->delete();
        return redirect()->route('short-urls.index')->with('success', 'Short URL deleted successfully.');
    }

    public function show($shortCode)
    {
        $shortUrl = ShortUrl::where('short_code', $shortCode)->firstOrFail();
        $shortUrl->increment('click_count');


        return redirect($shortUrl->original_url);
    }

    public function download(Request $request)
    {
        $user = auth()->user();
        $query = ShortUrl::with(['company', 'user']);
        if ($user->hasRole('Admin')) {
            $query->where('company_id', $user->company_id);
        } elseif ($user->hasRole('Member')) {
            $query->where('company_id', $user->company_id)
                ->where('user_id', $user->id);
        }
        if ($request->filter === 'today') {
            $query->whereDate('created_at', now());
        }

        if ($request->filter === 'last_week') {
            $query->where('created_at', '>=', now()->subWeek());
        }

        if ($request->filter === 'last_month') {
            $query->where('created_at', '>=', now()->subMonth());
        }

        $shortUrls = $query->get();

        return response()->streamDownload(function () use ($shortUrls, $user) {

            $handle = fopen('php://output', 'w');
            $headings = [
                'Short Code',
                'Original URL',
                'Clicks',
            ];

            if ($user->hasRole('SuperAdmin')) {
                $headings[] = 'Company';
            }

            if ($user->hasRole('Admin') || $user->hasRole('Member')) {
                $headings[] = 'Created By';
            }

            $headings[] = 'Created At';

            fputcsv($handle, $headings);

            foreach ($shortUrls as $url) {

                $row = [
                    $url->short_code,
                    $url->original_url,
                    $url->click_count,
                ];

                if ($user->hasRole('SuperAdmin')) {
                    $row[] = optional($url->company)->name;
                }

                if ($user->hasRole('Admin') || $user->hasRole('Member')) {
                    $row[] = optional($url->user)->name;
                }

                $row[] = $url->created_at->format('d M Y');

                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 'short-urls.csv');
    }
}
