<div id="invisible-modal" 
     x-data="{ notifications: [] }"
     class="fixed inset-0 z-50 pointer-events-none">
    
    <template x-for="(notif, index) in notifications" :key="index">
        <div :class="notif.positionClasses" class="flex flex-col gap-2 pointer-events-auto">
            <div x-html="notif.content"></div>
        </div>
    </template>
</div>
