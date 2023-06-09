<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;

use function PHPUnit\Framework\returnSelf;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.index', [
            "articles" => Article::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        // $user = Auth::user();
        // dd(Auth::check());
        return view('admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreArticleRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreArticleRequest $request)
    {
        $id = Auth::id();

        $validatedData = $request->validated();

        Article::create([
            'title' => $validatedData['title'],
            'slug' => $validatedData['title'],
            'body' => $validatedData['body'],
            'user_id' => $id
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Article $article)
    {
        return view('admin.articles.show', [
            'article' => $article
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request, Article $article)
    {
        // if (! Gate::authorize('update-article', $article)) {
        //     abort(403);
        // }

        // return view('admin.articles.edit', [
        //     'article' => $article
        // ]);

        $responce = Gate::inspect('update-article', $article);

        if ($responce->allowed()) {
            return view('admin.articles.edit', [
                'article' => $article
            ]);
        } else {
            echo "skdjbksaefuods'psfj's";
        }

        // if ($request->user()->cannot('update', $article)) {
        //     abort(403);
        // } else {
        //     return view ('admin.articles.edit', [
        //         'article' => $article
        //     ]);
        // }

        // return view('admin.articles.edit', [
        //     'article' => $article
        // ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        if (! Gate::authorize('update-article', $article)) {
            abort(403);
        }

        $validatedData = $request->validated();

        $article->update($validatedData);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return back();
    }
}
