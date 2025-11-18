import './bootstrap';

// Import FullCalendar packages via ESM so Vite bundles them.
import * as FullCalCore from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import resourceTimelinePlugin from '@fullcalendar/resource-timeline';

// Expose a `FullCalendar` global similar to the CDN global so existing inline scripts work.
window.FullCalendar = {
	...FullCalCore,
	interactionPlugin,
	dayGridPlugin,
	resourceTimelinePlugin
};

// Optional: expose Calendar constructor directly for convenience
if (FullCalCore.Calendar) {
	window.FullCalendar.Calendar = FullCalCore.Calendar;
}
