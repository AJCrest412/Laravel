<x-layout>
    <x-card>
        <form action="{{ route('employer.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <x-lable for="company_name" :required="true">Company Name</x-lable>
                <x-text-input name="company_name" />
            </div>

            <x-button class="w-full">Create</x-button>
        </form>
    </x-card>
</x-layout>
