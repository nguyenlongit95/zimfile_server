<?php

namespace App\Exports;

use App\Models\Director;
use App\Models\Jobs;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Ramsey\Uuid\Type\Integer;

class JobsExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    public $directors;
    public $month;

    /**
     * JobsExport constructor.
     * @param $param
     */
    public function __construct($param)
    {
        $directors = Director::join('users', 'directors.user_id', 'users.id')
            ->where(function ($query) use ($param) {
                if (isset($param['month'])) {
                    $query->whereMonth('directors.created_at', Carbon::now()->subMonths($param['month'])->format('m'))->where('directors.level', 2);
                }
                if (isset($param['date'])) {
                    $query->whereDate('directors.created_at', '>=', $param['date_from']);
                    $query->whereDate('directors.created_at', '<=', $param['date_to']);
                }
                return $query;
            })
            ->where('directors.parent_id', '<>', 0)
            ->select(
                'users.id', 'users.name as client',
                'directors.id', 'directors.nas_dir', 'directors.type', 'directors.status', 'directors.created_at',
                'directors.editor_id', 'directors.note', 'directors.qty as quantity'
            )->get();

        // compare and merge text for const number
        if (!empty($directors)) {
            foreach ($directors as $director) {
                $director = Director::convertStatus($director);
                $director = Director::convertType($director);
                $director->date_create = Carbon::now()->format('m-Y');
                $editor = User::where('id', $director->editor_id)->select('name')->first();
                if (empty($editor)) {
                    $director->editor = '-';
                } else {
                    $director->editor = $editor->name;
                }
            }
        } else {
            return null;
        }
        // add data param
        $this->directors = $directors;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Return data
        return $this->directors;
    }

    /**
     * Returns headers for report
     * @return array
     */
    public function headings(): array {
        return [
            'ID',
            'Date',
            'Order name',
            'Quantity',
            'Client',
            'Type',
            'Editor',
            'QC',
            'Instruction',
            'INVOICE',
        ];
    }

    /**
     * @param $director
     * @return array
     */
    public function map($director): array {
        return [
            $director->id,
            $director->date_create,
            $director->nas_dir,
            $director->quantity,
            $director->client,
            $director->type_txt,
            $director->editor,
            $director->status_txt,
            $director->note,
            '',
        ];
    }

    /**
     * @return array
     */
    public function registerEvents() : array {

        return [
            AfterSheet::class => function (AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;
                //  // 0 adminstator, 1 user, 2 editor, 3 qc
                $editors = User::where('role', 2)->where('status', 1)->select('name')->get();
                $clients = User::where('role', 1)->where('status', 1)->select('name')->get();
                $qcs = User::where('role', 3)->where('status', 1)->select('name')->get();
                $strEditors = '';
                if (!empty($editors)) {
                    foreach ($editors as $editor) {
                        $strEditors .= $editor->name . ',';
                    }
                }
                $strClients = '';
                if (!empty($clients)) {
                    foreach ($clients as $client) {
                        $strClients .= $client->name. ',';
                    }
                }
                $strQcs = '';
                if (!empty($qcs)) {
                    foreach ($qcs as $qc) {
                        $strQcs .= $qc->name . ',';
                    }
                }
                // type: 1: Photo editing, 2: Day to dusk, 3: Virtual Staging,	4: Additional Retouching
                $strType = 'Photo editing, Day to dusk, Virtual Staging, Additional Retouching';
                // 0: reject, 1 chưa assign, 2 đã asign, 3 confirm, 4 done
                $strStatus = 'Reject, None assign, Assigned, Confirm, Done';
                /**
                 * validation for bulkuploadsheet
                 */
                $index = 2;
                foreach ($this->directors as $director) {
                    // Cells for clients
                    $sheet->setCellValue('E' . $index, $director->client);
                    $cellClients = $sheet->getCell('E' . $index)->getDataValidation();
                    $cellClients->setType(DataValidation::TYPE_LIST);
                    $cellClients->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $cellClients->setAllowBlank(false);
                    $cellClients->setShowInputMessage(true);
                    $cellClients->setShowErrorMessage(true);
                    $cellClients->setShowDropDown(true);
                    $cellClients->setErrorTitle('Input error');
                    $cellClients->setError('Value is not in list.');
                    $cellClients->setPromptTitle('Pick from list');
                    $cellClients->setPrompt('Please pick a value from the drop-down list.');
                    $cellClients->setFormula1('"' . $strClients . '"');

                    // Cells for types
                    $sheet->setCellValue('F' . $index, $director->type_txt);
                    $cellTypes = $sheet->getCell('F' . $index)->getDataValidation();
                    $cellTypes->setType(DataValidation::TYPE_LIST);
                    $cellTypes->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $cellTypes->setAllowBlank(false);
                    $cellTypes->setShowInputMessage(true);
                    $cellTypes->setShowErrorMessage(true);
                    $cellTypes->setShowDropDown(true);
                    $cellTypes->setErrorTitle('Input error');
                    $cellTypes->setError('Value is not in list.');
                    $cellTypes->setPromptTitle('Pick from list');
                    $cellTypes->setPrompt('Please pick a value from the drop-down list.');
                    $cellTypes->setFormula1('"' . $strType . '"');

                    // Cells for editors
                    $sheet->setCellValue('G' . $index, $director->editor);
                    $cellTypes = $sheet->getCell('G' . $index)->getDataValidation();
                    $cellTypes->setType(DataValidation::TYPE_LIST);
                    $cellTypes->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $cellTypes->setAllowBlank(false);
                    $cellTypes->setShowInputMessage(true);
                    $cellTypes->setShowErrorMessage(true);
                    $cellTypes->setShowDropDown(true);
                    $cellTypes->setErrorTitle('Input error');
                    $cellTypes->setError('Value is not in list.');
                    $cellTypes->setPromptTitle('Pick from list');
                    $cellTypes->setPrompt('Please pick a value from the drop-down list.');
                    $cellTypes->setFormula1('"' . $strEditors . '"');

                    // Cells for qcs
                    $sheet->setCellValue('H' . $index, $director->status_txt);
                    $cellTypes = $sheet->getCell('H' . $index)->getDataValidation();
                    $cellTypes->setType(DataValidation::TYPE_LIST);
                    $cellTypes->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $cellTypes->setAllowBlank(false);
                    $cellTypes->setShowInputMessage(true);
                    $cellTypes->setShowErrorMessage(true);
                    $cellTypes->setShowDropDown(true);
                    $cellTypes->setErrorTitle('Input error');
                    $cellTypes->setError('Value is not in list.');
                    $cellTypes->setPromptTitle('Pick from list');
                    $cellTypes->setPrompt('Please pick a value from the drop-down list.');
                    $cellTypes->setFormula1('"' . $strStatus . '"');

                    // Cells for invoice
                    $sheet->setCellValue('J' . $index, $director->client);
                    $cellTypes = $sheet->getCell('J' . $index)->getDataValidation();
                    $cellTypes->setType(DataValidation::TYPE_LIST);
                    $cellTypes->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $cellTypes->setAllowBlank(false);
                    $cellTypes->setShowInputMessage(true);
                    $cellTypes->setShowErrorMessage(true);
                    $cellTypes->setShowDropDown(true);
                    $cellTypes->setErrorTitle('Input error');
                    $cellTypes->setError('Value is not in list.');
                    $cellTypes->setPromptTitle('Pick from list');
                    $cellTypes->setPrompt('Please pick a value from the drop-down list.');
                    $cellTypes->setFormula1('"' . $strClients . '"');

                    // Set style simple
                    $cellRange = 'A1:J1';
                    $event->sheet->getDelegate()
                        ->getStyle($cellRange)
                        ->getFont()
                        ->setSize(13)
                        ->getColor()->setRGB('0000ff');
                    $index++;
                }
            }
        ];
    }
}
