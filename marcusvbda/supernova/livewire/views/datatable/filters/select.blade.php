@livewire('supernova::select-field', [
    'field' => $field,
    'onchange' => "filters[$field]:changed",
    'multiple' => true,
])
{{ json_encode($filters) }}
