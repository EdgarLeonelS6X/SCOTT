<?php

namespace App\Livewire\App\Reports;

use Livewire\Component;
use App\Models\Comment;
use App\Models\Report;
use App\Events\CommentAdded;

class ReportCommentsModal extends Component
{
    public $reportId;
    public $body = '';
    public $editingId = null;
    public $editBody = '';

    protected $rules = [
        'body' => 'required|string|max:1000',
        'editBody' => 'required|string|max:1000',
    ];

    protected $listeners = [
        'refreshComments' => '$refresh',
    ];

    public function addComment()
    {
        $this->validateOnly('body');

        $comment = Comment::create([
            'report_id' => $this->reportId,
            'user_id' => auth()->id(),
            'body' => $this->body,
        ]);

        $this->body = '';
        broadcast(new CommentAdded($comment))->toOthers();
        $this->dispatch('refreshComments');
    }

    public function editComment($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== auth()->id())
            return;
        $this->editingId = $id;
        $this->editBody = $comment->body;
    }

    public function updateComment($id)
    {
        $this->validateOnly('editBody');
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== auth()->id())
            return;
        $comment->body = $this->editBody;
        $comment->save();
        $this->editingId = null;
        $this->editBody = '';
        broadcast(new CommentAdded($comment))->toOthers();
        $this->dispatch('refreshComments');
    }

    public function cancelEdit()
    {
        $this->editingId = null;
        $this->editBody = '';
    }

    public function deleteComment($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== auth()->id())
            return;
        $comment->delete();
        broadcast(new CommentAdded($comment))->toOthers();
        $this->dispatch('refreshComments');
    }

    public function render()
    {
        $comments = Comment::with('user')
            ->where('report_id', $this->reportId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.app.reports.report-comments-modal', [
            'comments' => $comments,
        ]);
    }
}
