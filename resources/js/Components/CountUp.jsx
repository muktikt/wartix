import { useEffect, useState } from 'react';

export default function CountUp({ end, duration = 1600, prefix = '', suffix = '', decimals = 0 }) {
    const [count, setCount] = useState(0);

    useEffect(() => {
        let startTime = null;
        let animationFrameId = null;

        const target = typeof end === 'number' 
            ? end 
            : parseFloat(String(end ?? 0).replace(/[^0-9.-]+/g, '')) || 0;

        function animate(currentTime) {
            if (!startTime) startTime = currentTime;
            const progress = Math.min((currentTime - startTime) / duration, 1);
            
            // Cubic ease-out curve for smooth deceleration
            const easeProgress = 1 - Math.pow(1 - progress, 3);
            const currentVal = easeProgress * target;

            setCount(currentVal);

            if (progress < 1) {
                animationFrameId = requestAnimationFrame(animate);
            }
        }

        animationFrameId = requestAnimationFrame(animate);

        return () => {
            if (animationFrameId) {
                cancelAnimationFrame(animationFrameId);
            }
        };
    }, [end, duration]);

    const targetVal = typeof end === 'number' 
        ? end 
        : parseFloat(String(end ?? 0).replace(/[^0-9.-]+/g, '')) || 0;

    const hasDecimals = decimals > 0 || (targetVal % 1 !== 0);

    const formatted = hasDecimals 
        ? count.toLocaleString('id-ID', { minimumFractionDigits: decimals || 1, maximumFractionDigits: decimals || 1 })
        : Math.round(count).toLocaleString('id-ID');

    return (
        <span>
            {prefix}{formatted}{suffix}
        </span>
    );
}
