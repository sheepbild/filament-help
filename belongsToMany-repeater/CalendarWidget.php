<?php

namespace App\Filament\Resources\TherapieResource\Widgets;

use App\Models\Event;
use App\Models\User;
use DateTime;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Carbon;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Actions\ViewAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public string|null|\Illuminate\Database\Eloquent\Model $model = Event::class;
    public $journee;
    public $start;
    public $end;
    public $user;

    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {
        $start = new DateTime($fetchInfo['start']);
        $end = new DateTime($fetchInfo['end']);

        return Event::query()
            ->where('start', '>=', $start->format('Y-m-d H:i:s'))
            ->where('end', '<=', $end->format('Y-m-d H:i:s'))
            ->where('discipline_type', 'yoga')
            ->get()
            ->map(
                fn (Event $event) => [
                    'id' => $event->id,
                    'title' => $event->yoga->intitule,
                    'start' => Carbon::parse($event->start),
                    'end' => Carbon::parse($event->end),
                    //'url' => 'brrr',//EventResource::getUrl(name: 'view', parameters: ['record' => $event]),
                    //'shouldOpenUrlInNewTab' => true
                ]
            )
            ->all();
    }

    protected function headerActions(): array{
        return [
            CreateAction::make(),
        ];
    }

    protected function modalActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function viewAction(): ViewAction
    {
        return ViewAction::make();
    }

    public function getFormSchema(): array
    {
        return [
            DateTimePicker::make('start')
                ->label('Début')
                ->default(now())
                ->seconds(false)
                ->required(),
            DateTimePicker::make('end')
                ->label('Fin')
                ->default(now())
                ->seconds(false)
                ->required(),
            Repeater::make('reservations')
                ->relationship('reservations')
                ->schema([
                    Select::make('student_id')
                        ->label('Participant')
                        ->required()
                        ->relationship('students', 'name')
                        ->options(User::all()->sortBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->default(null),
                    Toggle::make('has_participated')
                        ->label('A participé'),
                    Toggle::make('is_paid')
                        ->label('Est payé'),
                ])
                ->columns(3)
        ];
    }
}
