function openFilterModal() {
    document.getElementById('filter-modal').classList.remove('hidden');
}

function closeFilterModal() {
    document.getElementById('filter-modal').classList.add('hidden');
}

function switchTab(type) {
    const filter = document.getElementById('content-filter');
    const sort = document.getElementById('content-sort');

    const tabFilter = document.getElementById('tab-filter');
    const tabSort = document.getElementById('tab-sort');

    if (type === 'filter') {
        filter.classList.remove('hidden');
        sort.classList.add('hidden');

        tabFilter.classList.add('border-emerald-500','text-emerald-600');
        tabSort.classList.remove('border-emerald-500','text-emerald-600');
    } else {
        sort.classList.remove('hidden');
        filter.classList.add('hidden');

        tabSort.classList.add('border-emerald-500','text-emerald-600');
        tabFilter.classList.remove('border-emerald-500','text-emerald-600');
    }
}

window.openFilterModal = openFilterModal;
window.closeFilterModal = closeFilterModal;
window.switchTab = switchTab;