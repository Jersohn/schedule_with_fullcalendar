<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class FullCalendarController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Event::whereDate('start', '>=', $request->start)
                ->whereDate('end', '<=', $request->end)
                ->get(['id', 'title', 'teacher', 'classe', 'subject', 'start', 'end', 'color']);
            return response()->json($data);
        }

        return view('full-calendar'); // Assurez-vous que le nom de la vue est correct
    }

    public function action(Request $request)
    {
        if ($request->ajax()) {
            $event = null;  // Initialisez l'objet $event à null

            try {
                switch ($request->type) {
                    case 'add':
                        $event = Event::create([
                            'title' => $request->title,
                            'teacher' => $request->teacher,
                            'classe' => $request->classe,
                            'subject' => $request->subject,
                            'start' => $request->start,
                            'end' => $request->end,
                            'color' => $request->color,
                        ]);
                        break;

                    case 'update':
                        $event = Event::findOrFail($request->id);
                        $event->update([
                            'title' => $request->title,
                            'teacher' => $request->teacher,
                            'classe' => $request->classe,
                            'subject' => $request->subject,
                            'start' => $request->start,
                            'end' => $request->end,
                            'color' => $request->color,
                        ]);
                        break;

                    case 'delete':
                        Event::destroy($request->id);
                        break;

                    default:
                        // Gérez d'autres types d'actions si nécessaire
                        break;
                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            return response()->json($event);
        }
    }
}
