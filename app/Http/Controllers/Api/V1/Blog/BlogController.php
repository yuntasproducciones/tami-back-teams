<?php

namespace App\Http\Controllers\Api\V1\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostBlog\PostStoreBlog;
use App\Repositories\Blog\BlogRepositoryInterface;

class BlogController extends Controller
{
    protected $blogRepository;
    
    public function __construct(BlogRepositoryInterface $blogRepository) {
        $this->blogRepository = $blogRepository;
    }

    public function index()
    {
        return $this->blogRepository->getAll();
    }

    public function store(PostStoreBlog $request)
    {
        return $this->blogRepository->create($request->validated());
    }

    public function show($id)
    {
        return $this->blogRepository->find($id);
    }

    public function update(PostStoreBlog $request, $blog)
    {
        return $this->blogRepository->update($request->validated(), $blog);
    }

    public function destroy($id)
    {
        return $this->blogRepository->delete($id);
    }
}
