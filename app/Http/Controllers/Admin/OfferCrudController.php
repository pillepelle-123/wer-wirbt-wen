<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OfferRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OfferCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OfferCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Offer::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/offer');
        CRUD::setEntityNameStrings('offer', 'offers');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(OfferRequest::class);
        CRUD::setFromDb(); // set fields from db columns.


        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
        // - CRUD::field('user_id')->type('hidden')->value(backpack_auth()->id());

        // $this->crud->addField([
        //     'name' => 'user_id',
        //     'type' => 'hidden',
        //     'value' => backpack_auth()->id(),
        // ]);
        $this->crud->addField([
            'name' => 'offered_by',
            'label' => 'Angebot von',
            'type' => 'select_from_array',
            'options' => [
                'referrer' => 'Referrer (Weiterempfehler)',
                'referred' => 'Referred (Empfohlener)'
            ],
            'allows_null' => false,
            'default' => 'referrer',
        ]);

        $this->crud->addField([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select_from_array',
            'options' => [
                'active' => 'Aktiv',
                'inactive' => 'Inaktiv',
                'matched' => 'Zugewiesen',
                'closed' => 'Abgeschlossen'
            ],
            'allows_null' => false,
            'default' => 'active',
        ]);

        // Für company_id
        $this->crud->addField([
            'name' => 'company_id',
            'label' => 'Unternehmen',
            'type' => 'select',
            'entity' => 'company',
            'attribute' => 'name',
            'data_source' => url('api/search/companies'),
            'placeholder' => 'Unternehmen suchen...',
            'minimum_input_length' => 2,
        ]);

        // Für user_id (falls nicht automatisch gesetzt)
        $this->crud->addField([
            'name' => 'user_id',
            'label' => 'Benutzer',
            'type' => 'select',
            'entity' => 'user',
            'attribute' => 'name',
            'data_source' => url('api/search/users'),
            'placeholder' => 'Benutzer suchen...',
            'minimum_input_length' => 2,
        ]);

        
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    
}
