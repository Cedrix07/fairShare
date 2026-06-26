<?php
namespace App\Service;

use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class MessageService
{
    public function messagesByGroup(Group $group, User $user)
    {
        if(!$group->members()->where('user_id', $user->id)->exists()){
            throw new \Exception('You are not a member of this group.');
        }

        return $group->messages()->with('user:id,name')->get();
    }

    public function create(Group $group, array $data, User $user)
    {
        // Check if user is a member of the group
        if(!$group->members()->where('user_id', $user->id)->exists()){
            throw new \Exception('You are not a member of this group.');
        }

        $imagePath = null;

        if(isset($data['image'])){
            $imagePath = $data['image']->store('message-proofs', 'public');
        }

        return Message::create([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'message' => $data['message'] ?? null,
            'image_path' => $imagePath,
        ]);
    }

    public function delete(Message $message, User $user)
    {
        // Check if the user is the sender of the message
        if($message->user_id !== $user->id){
            throw new \Exception('You can only delete your own messages.');
        }

        // Delete the image from storage if it exists
        if($message->image_path) {
            Storage::disk('public')->delete($message->image_path);
        }

        $message->delete();
    }
}
