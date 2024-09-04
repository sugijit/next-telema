function showTooltip() {
    const tooltip = document.getElementById('tooltip');
    tooltip.style.opacity = '1';
    tooltip.style.pointerEvents = 'auto';
}

function hideTooltip() {
    const tooltip = document.getElementById('tooltip');
    tooltip.style.opacity = '0';
    tooltip.style.pointerEvents = 'none';
}

document.getElementById('table_name').addEventListener('input', function() {
    this.value = this.value.replace(/\s+/g, '');
});