import { TextControl, DateTimePicker } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { registerPlugin } from '@wordpress/plugins';
import { __ } from "@wordpress/i18n";

const EventDetailsPanel = (props) => {

    const { getCurrentPostType } = useSelect('core/editor');
    const postType = getCurrentPostType();

    if (postType !== 'event') {
        return null;
    }

    const [meta, setMeta] = useEntityProp('postType', postType, 'meta');

    const getCustomMetaValue = (fieldName) => {
        return meta?.[fieldName] || '';
    };

    const updateCustomMeta = (newValue, fieldName) => {
        setMeta({ ...meta, [fieldName]: newValue });
    };

    return (
        <PluginDocumentSettingPanel
            name="event-details-panel"
            title={__("Event Details", "event-details")}
            className="event-details-panel"
        >
            <TextControl
                label="Location"
                value={getCustomMetaValue('event_location')}
                onChange={
                    (value) => updateCustomMeta(value, 'event_location')
                }
            />
            <div class="date-picker-wrapper">
                <DateTimePicker
                    onChange={
                        (value) => updateCustomMeta(value, 'event_date')
                    }
                    currentDate={getCustomMetaValue('event_date')}
                    is12Hour={true}
                />
            </div>
        </PluginDocumentSettingPanel>
    );
}

registerPlugin('event-details', {
    render: EventDetailsPanel,
});