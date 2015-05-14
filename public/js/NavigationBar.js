'use strict';

GitLog.NavigationBar = React.createClass({
    render: function () {
        var Navbar = ReactBootstrap.Navbar;
        var CollapsibleNav = ReactBootstrap.CollapsibleNav;
        var Nav = ReactBootstrap.Nav;
        var NavItem = ReactBootstrap.NavItem;
        var DropdownButton = ReactBootstrap.DropdownButton;
        var MenuItem = ReactBootstrap.MenuItem;
        var Input = ReactBootstrap.Input;

        var userLink = '#/' + username;

        return (
            <Navbar brand={GitLog.name} toggleNavKey={0} href="#/">
                <CollapsibleNav eventKey={0}> {/* This is the eventKey referenced */}
                    <Nav navbar>
                        <NavItem eventKey={1} href='#/Dashboard'>Dashboard</NavItem>
                        <NavItem eventKey={2} href='#'>Link</NavItem>
                        <DropdownButton eventKey={3} title='Dropdown'>
                            <MenuItem eventKey='1'>Action</MenuItem>
                            <MenuItem eventKey='2'>Another action</MenuItem>
                            <MenuItem eventKey='3'>Something else here</MenuItem>
                            <MenuItem divider />
                            <MenuItem eventKey='4'>Separated link</MenuItem>
                        </DropdownButton>
                    </Nav>
                    <Nav navbar right>
                        <NavItem eventKey={1} href={userLink}>{username}</NavItem>
                        <NavItem eventKey={2} href='/sign/in'>Sign in</NavItem>
                    </Nav>
                </CollapsibleNav>
            </Navbar>
        )
    }
});
