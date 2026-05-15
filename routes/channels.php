<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('farmer.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id && $user->role === 'farmer';
});
