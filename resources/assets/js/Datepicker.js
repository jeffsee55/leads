import React from 'react';
import ReactDOM from 'react-dom';
import moment from 'moment';
import { DateRangePicker } from 'react-dates';
import { DayPicker } from 'react-dates';

class DateRangePickerWrapper extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            className: null,
            focusedInput: null,
            startDate: null,
            endDate: null,
            numberOfMonths: 2,
            withPortal: false,
            displayFormat: 'L',
            enableOutsideDays: false,
            unavailableDates: [],
            checkinUnavailable: [],
            checkoutUnavailable: [],
            minimumNights: 1,
            minimumNightsArray: []
        };

        this.getParam = this.getParam.bind(this);
        this.setStartDate = this.setStartDate.bind(this);
        this.setEndDate = this.setEndDate.bind(this);
        this.onDatesChange = this.onDatesChange.bind(this);
        this.setMinimumNights = this.setMinimumNights.bind(this);
        this.onFocusChange = this.onFocusChange.bind(this);
        this.isDayBlocked= this.isDayBlocked.bind(this);
        this.isCheckinUnavailable = this.isCheckinUnavailable.bind(this)
        this.isCheckoutUnavailable = this.isCheckoutUnavailable.bind(this)
        this.setResponsiveness = this.setResponsiveness.bind(this);
    }

    getParam(name) {
        var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
        return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
    }

    setStartDate() {
        var startDate = this.getParam('start_date');
        if(startDate) {
            this.setState({startDate: moment(startDate)});
        }
    }

    setEndDate() {
        var endDate = this.getParam('end_date');
        if(endDate) {
            this.setState({endDate: moment(endDate)});
        }
    }

    onDatesChange({ startDate, endDate }) {
        this.setState({ startDate, endDate });

        if(this.state.focusedInput == 'startDate') {
            this.setMinimumNights(startDate);
        }
    }

    setMinimumNights(startDate) {
        this.state.minimumNightsArray.map((night) => {
            var intervalStartDate = moment(night.start_date);
            var intervalEndDate = moment(night.end_date);
            if(startDate.isBetween(intervalStartDate, intervalEndDate)){
                this.setState({ minimumNights: night.nights });
            } else {
                console.log('false');
            }
        });
    }

    onFocusChange(focusedInput) {
        this.setState({ focusedInput });
    }

    setResponsiveness(e) {
        if(window.innerWidth < '720') {
            this.setState({ numberOfMonths: 1 });
        } else {
            this.setState({ numberOfMonths: 2 });
        }
    }

    componentWillMount() {
        this.setStartDate();
        this.setEndDate();
        this.setResponsiveness();
        this.setState({ className: '' });
    }

    componentDidMount() {
        window.addEventListener("resize", this.setResponsiveness);
        var unitInput = document.getElementById('unitCode');
        if(unitInput.value.length > 0) {
            var unitCode = unitInput.value;
            jQuery.ajax({
                url: `/wp-admin/admin-ajax.php?action=q4vr_calendar&unit_code=${unitCode}`,
                dataType: 'json',
                cache: false,
                success: function(results) {
                    this.setState({unavailableDates: results.data.unavailable});
                    this.setState({minimumNightsArray: results.data.minimumNights});
                }.bind(this),
                error: function(xhr, status, err) {
                    console.log(this.props.url, status, err.toString());
                }.bind(this)
            });
            var startDate = document.getElementById("start_date");
            var endDate = document.getElementById("end_date");
            startDate.setAttribute("required", true);
            endDate.setAttribute("required", true);

            // run the availability search on page load
            if(startDate.value && endDate.value) {
                document.getElementById("searchSubmit").click();
            }
        }
    }

    isCheckinUnavailable(day) {
        return this.state.checkinUnavailable.includes(day.format('YYYY-MM-DD'));
    }

    isCheckoutUnavailable(day) {
        return this.state.checkoutUnavailable.includes(day.format('YYYY-MM-DD'));
    }

    isDayBlocked(day) {
        return this.state.unavailableDates.includes(day.format('YYYY-MM-DD'));
    }

    render() {
        const { className, focusedInput, displayFormat, startDate, endDate, numberOfMonths, withPortal, checkinUnavailable, checkoutUnavailable, enableOutsideDays, minimumNights } = this.state;
        return (
            <div>
            <DateRangePicker
            {...this.props}
            className={className}
            onDatesChange={this.onDatesChange}
            onFocusChange={this.onFocusChange}
            focusedInput={focusedInput}
            startDateId='start_date'
            startDatePlaceholderText='Arrive'
            endDatePlaceholderText='Depart'
            startDate={startDate}
            endDateId='end_date'
            endDate={endDate}
            numberOfMonths={numberOfMonths}
            displayFormat={displayFormat}
            withPortal={withPortal}
            isDayBlocked={this.isDayBlocked}
            isCheckinUnavailable={this.isCheckinUnavailable}
            isCheckoutUnavailable={this.isCheckoutUnavailable}
            minimumNights={minimumNights}
            anchorDirection='left'
            />
            </div>
        );
    }
}

export default DateRangePickerWrapper;

if(document.getElementById('dates')) {
    ReactDOM.render(
        <DateRangePickerWrapper />, document.getElementById('dates')
    );
};
