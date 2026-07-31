import { motion } from 'framer-motion';

/**
 * Wraps content so it fades + slides into view the first time it scrolls
 * into the viewport. Use for stat cards, list rows, section headers, etc.
 *
 * <Reveal delay={0.1}><div>...</div></Reveal>
 */
export default function Reveal({ children, delay = 0, y = 24, className = '', once = true }) {
    return (
        <motion.div
            initial={{ opacity: 0, y }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once, amount: 0.2 }}
            transition={{ duration: 0.85, delay, ease: [0.16, 1, 0.3, 1] }}
            className={className}
        >
            {children}
        </motion.div>
    );
}
