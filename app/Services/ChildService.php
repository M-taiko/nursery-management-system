<?php

namespace App\Services;

use App\Models\Child;
use Illuminate\Support\Facades\Storage;

class ChildService
{
    public function getFilteredChildren(array $filters = [])
    {
        $query = Child::with(['stage', 'classroom', 'parent']);

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['stage_id'])) {
            $query->where('stage_id', $filters['stage_id']);
        }

        if (!empty($filters['classroom_id'])) {
            $query->where('classroom_id', $filters['classroom_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(15);
    }

    public function getChildWithRelations($id)
    {
        return Child::with([
            'stage',
            'classroom',
            'parent',
            'evaluations.subject',
            'evaluations.teacher',
            'photos',
            'behaviorRecords.teacher',
            'feeInvoices.feePlan',
            'feeInvoices.payments'
        ])->findOrFail($id);
    }

    public function createChild(array $data): Child
    {
        if (isset($data['photo'])) {
            $data['photo'] = $data['photo']->store('children', 'public');
        }

        return Child::create($data);
    }

    public function updateChild(Child $child, array $data): Child
    {
        if (isset($data['photo'])) {
            if ($child->photo) {
                Storage::disk('public')->delete($child->photo);
            }
            $data['photo'] = $data['photo']->store('children', 'public');
        }

        $child->update($data);
        return $child;
    }

    public function deleteChild(Child $child): bool
    {
        if ($child->photo) {
            Storage::disk('public')->delete($child->photo);
        }

        return $child->delete();
    }
}
