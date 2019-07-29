import React from 'react'

import DashboardView from './views/DashboradView'


class Dashboard extends React.Component {

    constructor(props) {
        super(props)
        this.state = {}
    }


    render() {
        return (
            <DashboardView />
        )
    }
}


export default Dashboard