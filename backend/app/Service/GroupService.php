<?php
namespace App\Service;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class GroupService
{
    private function generateInviteCode(): string
    {
       do { $code = Str::upper(Str::random(6)); }
        while (Group::where('invite_code', $code)->exists());
        return $code;
    }

    public function create(array $data, User $user): Group
    {
        $group = Group::create([
            'name' => $data['name'],
            'invite_code' => $this->generateInviteCode(),
            'created_by' => $user->id
        ]);
        $group->users()->attach($user->id);
        return $group;
    }

    public function joinGroup(string $inviteCode, User $user): Group
    {
        try {
            $group = Group::where('invite_code', strtoupper($inviteCode))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Invalid invite code.');
        }

        if ($group->users()->where('user_id', $user->id)->exists()) {
            throw new \Exception('User is already a member of this group.');
        }

        $group->users()->attach($user->id);

        return $group;
    }

    public function myGroups(User $user)
    {
        $groups = $user->groups()
            ->latest('groups.created_at')
            ->with(['creator:id,name', 'members:id,name'])
            ->get(['groups.id','groups.name', 'groups.invite_code', 'groups.created_by']);

        // Remove unnecessary pivot data from members
        $groups->each(function ($group){
            $group->members->makeHidden('pivot');
        });

        return $groups;
    }

    public function show(Group $group)
    {
        $group->load('creator:id,name', 'members:id,name');
        // Remove unnecessary pivot data from members
        $group->members->makeHidden('pivot');
        return $group;
    }

    public function delete(Group $group, User $user)
    {
        if ($group->created_by !== $user->id) {
            throw new \Exception('Only the creator can delete the group.');
        }

        $group->delete();
    }
}
