<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(): View
    {
        return view('rooms.index', [
            'rooms' => Room::withCount('bookings')->orderBy('price')->get(),
        ]);
    }

    public function create(): View
    {
        return view('rooms.form', ['room' => new Room]);
    }

    public function store(StoreRoomRequest $request): RedirectResponse
    {
        Room::create([
            ...$request->safe()->except('image'),
            'image_url' => $request->file('image')->store('rooms', 'public'),
        ]);

        return redirect()->route('rooms.index')->with('status', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Room $room): View
    {
        return view('rooms.form', compact('room'));
    }

    public function update(UpdateRoomRequest $request, Room $room): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $this->deleteUploadedImage($room);
            $data['image_url'] = $request->file('image')->store('rooms', 'public');
        }

        $room->update($data);

        return redirect()->route('rooms.index')->with('status', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        if ($room->bookings()->exists()) {
            return redirect()->route('rooms.index')->with('error', "Kamar {$room->name} tidak bisa dihapus karena sudah memiliki data pemesanan.");
        }

        $this->deleteUploadedImage($room);
        $room->delete();

        return redirect()->route('rooms.index')->with('status', 'Kamar berhasil dihapus.');
    }

    /**
     * Remove the room's uploaded image from the public disk, leaving external URLs untouched.
     */
    private function deleteUploadedImage(Room $room): void
    {
        if ($room->hasUploadedImage()) {
            Storage::disk('public')->delete($room->image_url);
        }
    }
}
