const MONTHS_ID = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

/**
 * Format an ISO date string as "d M Y" (e.g. "15 Jan 2026"), matching
 * Carbon's ->format('d M Y') used previously in Blade.
 */
export function formatDate(dateString) {
    if (!dateString) return '-';
    const d = new Date(dateString);
    if (Number.isNaN(d.getTime())) return '-';
    return `${String(d.getDate()).padStart(2, '0')} ${MONTHS_ID[d.getMonth()]} ${d.getFullYear()}`;
}

/** Format a number as Indonesian Rupiah, e.g. "Rp 150.000". */
export function formatRp(num) {
    return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
}
