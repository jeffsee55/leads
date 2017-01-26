import React from 'react';
import ReactDOM from 'react-dom';
import { ButtonGroup, DropdownButton, MenuItem, Button } from 'react-bootstrap';

class Counter extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            count: this.props.count,
            max: 10
        };

        this.add = this.add.bind(this);
        this.subtract = this.subtract.bind(this);
    }

    add() {
        if(this.state.count >= this.state.max) {
            this.setState({
                count: this.state.max
            })
        } else {
            this.setState({
                count: this.state.count + 1
            })
        }
    }

    componentDidMount()
    {
        var maxOccupancy = document.getElementById('maxOccupancy');
        if(maxOccupancy.value.length > 0) {
            this.state.max = maxOccupancy.value
        }
    }

    subtract() {
        if(this.state.count < 1) {
            this.setState({
                count: 0
            })
        } else {
            this.setState({
                count: this.state.count - 1
            })
        }
    }

    render() {
        return (
            <div>
            <div className="btn-group" role="group" aria-label="counter">
            <h5 className="text-center">{this.props.name}</h5>
            <span onClick={this.subtract} className="btn btn-default">-</span>
            <span className="btn btn-default">{this.state.count}</span>
            <input type="hidden" name="guests[]" value={this.state.count} />
            <span onClick={this.add} className="btn btn-default">+</span>
            </div>
            </div>
        );
    }
}


class DropdownWrapper extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            dropdownOpen: false,
            active: false,
            pets: false,
            max: 10,
            hide: false,
            class: 'btn-group pets-button'
        };

        this.toggle = this.toggle.bind(this);
        this.togglePets = this.togglePets.bind(this);
    }

    toggle() {
        this.setState({
            dropdownOpen: !this.state.dropdownOpen
        });
    }

    togglePets() {
        this.setState({
            active: !this.state.active,
            pets: !this.state.pets
        });
    }

    componentDidMount()
    {
        var petsPolicy = document.getElementById('petsPolicy');
        if(petsPolicy.value.length > 0) {
            if(petsPolicy.value == '0') {
                this.setState({ class: this.state.class + ' hide-button' })
            }
        }
        var maxOccupancy = document.getElementById('maxOccupancy');
        if(maxOccupancy.value.length > 0) {
            this.state.max = maxOccupancy.value
        }
    }

    render() {
        return (
            <DropdownButton
            id="GuestsDropdown"
            className="form-control"
            title="Guests"
            noCaret
            >
            <Counter
            name="Adults"
            count={2}
            />
            <Counter
            count={0}
            name="Children"
            />
            <div>
            <div
            className={this.state.class}
            role="group" aria-label="counter">
            <span className="btn btn-default pets-label">Pets</span>
            <Button
            className="pets-toggle"
            active={this.state.active}
            onClick={this.togglePets}
            bsStyle="primary"
            >
            <i className="fa fa-check"></i>
            </Button>
            <input
            type="hidden"
            name="pets"
            value={this.state.pets}
            />
            </div>
            </div>
            </DropdownButton>
        );
    }
}
export default DropdownWrapper

if(document.getElementById('q4vr_popover')) {
    ReactDOM.render(
        <DropdownWrapper />, document.getElementById('q4vr_popover')
    );
}
