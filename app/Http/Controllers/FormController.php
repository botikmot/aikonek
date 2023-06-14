<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\FormPage;
use App\Models\FormSection;
use App\Models\FormDataItem;

class FormController extends Controller
{
    public function store(Request $request)
    {
        // Create a new form
        $form = Form::create([
            'title' => $request->input('form_title'),
            'image' => $request->input('form_image'),
        ]);

        // Loop through each page in the form
        foreach ($request->input('pages') as $pageData) {
            // Create a new form page
            $page = FormPage::create([
                'title' => $pageData['title'],
                'form_id' => $form->id,
            ]);

            // Loop through each section in the page
            foreach ($pageData['sections'] as $sectionData) {
                // Create a new form section
                $section = FormSection::create([
                    'title' => $sectionData['title'],
                    'form_page_id' => $page->id,
                ]);

                // Loop through each data item in the section
                foreach ($sectionData['data_items'] as $dataItemData) {
                    // Create a new form data item
                    $dataItem = FormDataItem::create([
                        'label' => $dataItemData['label'],
                        'type' => $dataItemData['type'],
                        'position' => $dataItemData['position'],
                        'form_section_id' => $section->id,
                        'properties' =>  $dataItemData['properties'],
                    ]);

                    // Save form submissions for the data item

                    /* foreach ($dataItemData['submissions'] as $submissionData) {
                        // Create a new form submission
                        $submission = new FormSubmission([
                            'value' => $submissionData['value'],
                            'user_id' => $submissionData['user_id'],
                        ]);

                        // Associate the submission with the data item
                        $dataItem->submissions()->save($submission);
                    } */
                }
            }
        }

        // Redirect or return a response
        // ...
        return response()->json(['success' => true, 'form' => $form], 201);
    }
}
