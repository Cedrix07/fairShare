<?php
namespace App\Service;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use App\Models\Group;

class BillService
{

    public function billsByGroup(Group $group,  User $user)
    {
       if(!$group->members()->where('user_id', $user->id)->exists()){
            throw new \Exception('You are not a member of this group.');
        }

        return $group->bills()->with('creator')->get();
    }

    public function create(Group $group, array $data, User $user)
    {
         // Check if user is a member of the group
        if(!$group->members()->where('user_id', $user->id)->exists()){
            throw new \Exception('You are not a member of this group.');
        }

        $memberCount = $group->members()->count();
        // total_amount / member_count
        $amountPerMember = round($data['total_amount'] / $memberCount, 2);

        return Bill::create([
            'group_id' => $group->id,
            'created_by' => $user->id,
            'description' => $data['description'],
            'total_amount' => $data['total_amount'],
            'member_count' => $memberCount,
            'amount_per_member' => $amountPerMember
        ]);
    }

    public function show(Bill $bill,  User $user)
    {
        $isMember = $bill->group->members()->where('users.id', $user->id)->exists();

       if(!$isMember){
            throw new \Exception('You are not a member of this group.');
        }

        return $bill->load('creator:id,name', 'group:id,name');
    }

    public function delete(Bill $bill, User $user)
    {
        $isBillCreator = $bill->created_by === $user->id;
        $isBillGroupCreator = $bill->group->created_by === $user->id;
        // Check if the user is either the creator of the bill or the creator of the group
        if(!$isBillCreator && !$isBillGroupCreator){
            throw new \Exception('You do not have permission to delete this bill.');
        }

        return $bill->delete();
    }
}
