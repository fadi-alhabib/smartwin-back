<?php

namespace App\Http\Controllers\Api\Points;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/transfers')]
class TransferPointsController extends Controller
{
    // #[Get('/', middleware: 'auth:admin')]
    // public function index()
    // {
    //     // Return all /
    //     return $this->success(Transfer::all());
    // }

    #[Post('/', middleware: 'auth:sanctum')]
    public function store(Request $request)
    {
        // Validate incoming request data
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'country' => 'required|string',
            'phone'   => 'required|string',
            'points'  => 'required|integer|min:1000',
        ]);
        $user = $request->user();
        if ($data['points'] >= 1000 && $user->points >= $data['points']) {
            $user->points -= $data['points'];
            $user->save();
        } else {
            return $this->failed('ليس لديك نقاط كافية', 400);
        }

        // Create a new transfer
        $transfer = Transfer::create($data);

        return $this->success($transfer, 201);
    }

    // #[Get('/{transfer}', middleware: 'auth:admin')]
    // public function show(Transfer $transfer)
    // {

    //     return $this->success($transfer);
    // }

    // #[Put('/{transfer}', middleware: 'auth:admin')]
    // public function update(Request $request, Transfer $transfer)
    // {
    //     // Validate incoming request data (all fields are optional here)
    //     $data = $request->validate([
    //         'user_id' => 'sometimes|exists:users,id',
    //         'country' => 'sometimes|string',
    //         'phone'   => 'sometimes|string',
    //         'points'  => 'sometimes|integer',
    //     ]);

    //     // Update the transfer
    //     $transfer->update($data);

    //     return $this->success($transfer);
    // }

    // #[Delete('/{transfer}', middleware: 'auth:admin')]
    // public function destroy(Transfer $transfer)
    // {
    //     // Delete the transfer
    //     $transfer->delete();

    //     return $this->success(null, 204);
    // }
}
