import React from 'react'


import './../../assets/css/Registry.css'


const RegistryView = props => {
    let registryContent = ''
    if(props.registry.length === 0) {
        registryContent = <p className="registry__empty">Реестр пуст</p>
    } else {   
        registryContent = <ViewRegistry data={props.registry} />
    }

    return (
        <div className="content">
            <div className="content__body __center">
                <section className="registry">
                    <p className="registry__title">Реестр</p>
                    {registryContent}
                </section>
            </div>
        </div>
    )
}


export default RegistryView


const ViewRegistry = props => {
    return props.data.map((user, key) => 
        <div key={`user_${key}`} className="registry__user">
            <div className="registry__user_box-flex">
                <p className="registry__user_name">{user.name}</p>
            </div>
            <ViewContracts data={user.contracts}/>
        </div>
    )
}

const ViewContracts = props => {
    return props.data.map((contract, key) => 
        <div key={`contract_${key}`} className="registry__contract">
            <div className="registry__contract_box-flex">
                <p className="registry__contract_name">{contract.name}</p>
                <p className="registry__contract_date">{contract.dateOpening} / {contract.dateClosure}</p>
                {contract.status === 1 
                    ? <p className="registry__contract_status registry__contract_status-active">Активный</p>
                    : <p className="registry__contract_status registry__contract_status-no-active">Не активный</p>
                }
            </div>
            <ViewInvoices data={contract.invoices}/>
        </div>
    )
}

const ViewInvoices = props => {
    return props.data.map((invoice, key) => 
        <div key={`invoice_${key}`} className="registry__invoice">
            <p className="registry__invoice_date">Счет от: {invoice.date}</p>
            <p className="registry__invoice_amount">На сумму: {invoice.status.amount} р.</p>
            {invoice.status.isPayt === 1 
                ? <p className="registry__invoice_status registry__invoice_status-paid">Оплачен</p>
                : <p className="registry__invoice_status registry__invoice_status-no-paid">Не оплачен</p>
            }
        </div>
    )
}
