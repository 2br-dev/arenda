import React from 'react'

import Loader from './../UI/Loader'

import './../../assets/css/Dashboard.css'


const DashboardView = props => {

    let registryContent = <Loader />
    // if(props.registryLoading === 0) {
    //     if(props.registry.)
    // }

    return (
        <div className="content">
            <div className="content__body __center">
                <section className="registry">
                    <p className="registry__title">Реестр</p>
                    <div className="registry__table">
                        {registryContent}
                        {/* <p className="registry__empty">Реестр пуст</p>
                        <p className="registry__name">Имя пользователя</p>
                        <div className="registry__box">
                            <p className="registry__sub-name">Название договора</p>
                            <div className="registry__sub-box">
                                <p className="registry__sub-sub-name">Название Счета</p>
                                <p className="registry__sub-sub-name">Название Счета</p>
                                <p className="registry__sub-sub-name">Название Счета</p>
                            </div>
                            <p className="registry__sub-name">Название договора</p>
                            <div className="registry__sub-box">
                                <p className="registry__sub-sub-name">Название Счета</p>
                                <p className="registry__sub-sub-name">Название Счета</p>
                                <p className="registry__sub-sub-name">Название Счета</p>
                            </div>
                            <p className="registry__sub-name">Название договора</p>
                            <div className="registry__sub-box">
                                <p className="registry__sub-sub-name">Название Счета</p>
                                <p className="registry__sub-sub-name">Название Счета</p>
                                <p className="registry__sub-sub-name">Название Счета</p>
                            </div>
                        </div>
                        <hr className="registry__hr"/> */}
                    </div>
                </section>
            </div>
        </div>
    )
}


export default DashboardView