<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Utils\EndPoint;

class AuthorController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $title = 'Authors';
            $page = $request->input('page', 1);
            $response =$this->withAuth()->get(EndPoint::URL ."/api/v2/authors?orderBy=id&direction=DESC&limit=12&page={$page}");
            if ($response->successful()) {
                $authors = $response->json();
                return view('layouts.authors.index',[
                    'authors' => $authors['items'] ?? [],
                    'current_page' => $authors['current_page'],
                    'total_pages' => $authors['total_pages'],
                    'total_results' => $authors['total_results'],
                    'limit' => $authors['limit'],
                    'offset' => $authors['offset'],
                    'title' => $title
                ]);
            }
            
            return back()->with('error', 'Failed to fetch authors');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while fetching authors');
        }
    }
    public function show($id)
    {
        try {
            $title = 'Author Details';
            
            $response =$this->withAuth()->get(EndPoint::URL . "/api/v2/authors/{$id}");

            if ($response->successful()) {
                $author = $response->json();
                return view('layouts.authors.detail', compact('author', 'title'));
            }

            return redirect()->route('authors')->with('error', 'Failed to fetch author details');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while fetching author details');
        }
    }

    public function destroy($id)
    {
        try {
            $token = session('access_token');
            
            // First, check if author has books
            $authorResponse = $this->withAuth()->get(EndPoint::URL . "/api/v2/authors/{$id}");

            if ($authorResponse->successful()) {
                $author = $authorResponse->json();
                
                if (count($author['books']) > 0) {
                    return back()->with('error', 'Cannot delete author with associated books');
                }

                // If no books, proceed with deletion
                $deleteResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json'
                ])->delete(EndPoint::URL . "/api/v2/authors/{$id}");

                if ($deleteResponse->successful()) {
                    return redirect()->route('authors')->with('success', 'Author deleted successfully');
                }

                return back()->with('error', 'Failed to delete author');
            }

            return back()->with('error', 'Failed to fetch author details');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting author');
        }
    }
}
