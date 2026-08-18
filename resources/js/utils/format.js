const MONTHS_ID = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

/**
 * Format an ISO date string as "d M Y" (e.g. "15 Jan 2026"), matching
 * Carbon's ->format('d M Y') used previously in Blade.
 */
export function formatDate(dateString) {
    if (!dateString) return '-';
    // Extract YYYY-MM-DD directly to prevent UTC timezone conversion shift (e.g. 20:00 UTC shifting to next day)
    const match = String(dateString).match(/^(\d{4})-(\d{2})-(\d{2})/);
    if (match) {
        const year = parseInt(match[1], 10);
        const month = parseInt(match[2], 10) - 1;
        const day = parseInt(match[3], 10);
        return `${String(day).padStart(2, '0')} ${MONTHS_ID[month]} ${year}`;
    }
    const d = new Date(dateString);
    if (Number.isNaN(d.getTime())) return '-';
    return `${String(d.getDate()).padStart(2, '0')} ${MONTHS_ID[d.getMonth()]} ${d.getFullYear()}`;
}

/** Format a number as Indonesian Rupiah, e.g. "Rp 150.000". */
export function formatRp(num) {
    return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
}
