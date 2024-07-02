
<x-app-layout>
    @livewire('chat-component', ['user_id' => $id])
    @stack('scripts')
</x-app-layout>