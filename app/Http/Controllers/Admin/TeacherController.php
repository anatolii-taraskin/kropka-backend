<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\ReorderRecords;
use App\Actions\Admin\Teacher\TeacherManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Teacher\ReorderTeacherRequest;
use App\Http\Requests\Admin\Teacher\StoreTeacherRequest;
use App\Http\Requests\Admin\Teacher\UpdateTeacherRequest;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function __construct(
        private readonly TeacherManager $teacherManager,
        private readonly ReorderRecords $reorderRecords,
    )
    {
    }

    /**
     * Display the list of teachers.
     */
    public function index(): View
    {
        $teachers = Teacher::query()
            ->orderBy('sort')
            ->orderBy('id')
            ->get();

        return view('admin.teachers.index', [
            'teachers' => $teachers,
        ]);
    }

    /**
     * Show the form for creating a new teacher entry.
     */
    public function create(): View
    {
        return view('admin.teachers.create');
    }

    /**
     * Store a newly created teacher entry.
     */
    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $this->teacherManager->create($request);

        return redirect()
            ->route('admin.teachers.index')
            ->with('status', 'teacher-created');
    }

    /**
     * Show the form for editing the specified teacher entry.
     */
    public function edit(Teacher $teacher): View
    {
        return view('admin.teachers.edit', [
            'teacher' => $teacher,
        ]);
    }

    /**
     * Update the specified teacher entry.
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        $this->teacherManager->update($request, $teacher);

        return redirect()
            ->route('admin.teachers.index')
            ->with('status', 'teacher-updated');
    }

    /**
     * Remove the specified teacher entry.
     */
    public function destroy(Teacher $teacher): RedirectResponse
    {
        $this->teacherManager->delete($teacher);

        return redirect()
            ->route('admin.teachers.index')
            ->with('status', 'teacher-deleted');
    }

    /**
     * Update the sort order for teachers.
     */
    public function reorder(ReorderTeacherRequest $request): JsonResponse
    {
        $this->reorderRecords->handle(Teacher::class, $request->ids());

        return response()->json(['status' => 'ok']);
    }
}
