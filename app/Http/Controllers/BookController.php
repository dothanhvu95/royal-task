<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Utils\EndPoint;

class BookController extends BaseController
{

    public function create()
    {
        try {
            $title = 'Add Book';
            
            // Fetch all authors for dropdown
            $response = $this->withAuth()->get(EndPoint::URL . '/api/v2/authors');

            if ($response->successful()) {
                $authors = $response->json()['items'];
                return view('layouts.books.create', compact('authors', 'title'));
            }

            return back()->with('error', 'Failed to fetch authors');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred');
        }
    }

public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|array',
            'release_date' => 'nullable|date',
            'description' => 'nullable|string|max:255',
            'isbn' => 'nullable|string',
            'format' => 'required|string',
            'number_of_pages' => 'nullable|integer'
        ]);
      
        try {
            $data = [
                'title' => $request->title,
                'author' => $request->author,
                'release_date' => $request->release_date,
                'description' => $request->description,
                'isbn' => $request->isbn,
                'format' => $request->format,
                'number_of_pages' => $request->number_of_pages ? (int)$request->number_of_pages : null
            ];
    
            $response = $this->withAuth()->post(EndPoint::URL . '/api/v2/books', $data);
            if ($response->successful()) {
                return redirect()->route('authors.show', $request->author['id'])
                            ->with('success', 'Book added successfully');
            } else {
                return back()->withInput()->with('error', $response->json()['message']);
            }
            return back()->withInput()->with('error', 'Failed to create book');

        } catch (\Exception $e) {
   
            return back()->withInput()->with('error', 'An error occurred');
        }
    }

    public function destroy($id)
    {
        try {
            $token = session('access_token');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->delete(EndPoint::URL . "/api/v2/books/{$id}");

            if ($response->successful()) {
                return back()->with('success', 'Book deleted successfully');
            }

            return back()->with('error', 'Failed to delete book');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting book');
        }
    }
}
