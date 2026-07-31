import { motion } from 'framer-motion';

const container = {
    hidden: {},
    show: {
        transition: { staggerChildren: 0.14 },
    },
};

const item = {
    hidden: { opacity: 0, y: 24 },
    show: { opacity: 1, y: 0, transition: { duration: 0.8, ease: [0.16, 1, 0.3, 1] } },
};

/**
 * Wrap a list/grid of items so each child fades+slides in one after another
 * as the group scrolls into view. Children must be direct elements (each
 * gets wrapped in a motion item automatically via RevealList.Item), or pass
 * plain children and they'll stagger as a group.
 *
 * <RevealList className="grid grid-cols-3 gap-4">
 *   <RevealList.Item><Card/></RevealList.Item>
 *   <RevealList.Item><Card/></RevealList.Item>
 * </RevealList>
 */
export default function RevealList({ children, className = '' }) {
    return (
        <motion.div
            className={className}
            initial="hidden"
            whileInView="show"
            viewport={{ once: true, amount: 0.15 }}
            variants={container}
        >
            {children}
        </motion.div>
    );
}

RevealList.Item = function RevealItem({ children, className = '' }) {
    return (
        <motion.div className={className} variants={item}>
            {children}
        </motion.div>
    );
};
