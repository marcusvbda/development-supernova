<div class="w-full text-center flex flex-col items-center justify-center my-20">
    @if ($icon)
        <div class="w-[125px] h-[125px] dark:stroke-white stroke-neutral-800">
            {!! $icon !!}
        </div>
    @endif
    <h4 class="text-2xl text-neutral-800 font-bold dark:text-neutral-200 mt-4 mb-1 flex items-center">
        Nenhum registro de {{ strtolower($name[0]) }} encontrado !
    </h4>
    @if ($canCreate)
        <small class="text-neutral-600">
            Clique em <strong>'Cadastrar'</strong> para adicionar um novo registro
        </small>
    @endif
</div>
