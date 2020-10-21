function addClass() {
    var grid = document.getElementById('button_grid');
    if (grid.className.includes('disabled')) {
        grid.className = grid.className.replace('disabled', 'enabled');
    } else if (grid.className.includes('enabled')) {
        grid.className = grid.className.replace('enabled', 'disabled');
    }
}