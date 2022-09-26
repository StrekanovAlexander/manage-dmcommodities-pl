import React, { useState, useEffect } from 'react'
import Loading from './components/Loading.jsx'
import Price from './components/Price/Price.jsx'
  
function App() { 
    // const basePricesUrl = 'http://manage-dmcommodities-pl.loc/base-prices/json'
    // const productPricesUrl = 'http://manage-dmcommodities-pl.loc/product-prices/json'
    const basePricesUrl = 'https://manage.dmcommodities.pl/base-prices/json'
    const productPricesUrl = 'https://manage.dmcommodities.pl/product-prices/json'

    const [ basePrices, setBasePrices ] = useState([])
    const [ productPrices, setProductPrices ] = useState([])
    const [ isPriceChanged, setIsPriceChanged ] = useState(false)
    const [ isPriceLoading, setIsPriceLoading ] = useState(false)

    useEffect(() => {
        loadPrice();
    }, [])

    function loadPrice() {
        setIsPriceChanged(false)
        setIsPriceLoading(true)
        setTimeout(() => {
            fetch(basePricesUrl).then(res => res.json()).then(data => setBasePrices(data))
            fetch(productPricesUrl).then(res => res.json()).then(data => setProductPrices(data))
            setIsPriceLoading(false)
        }, 1000)      
    }

    function changeBasePrices(id, price) {
        const changedBasePrices = basePrices.map(el => {
            if (id == el.id) {
                return { ...el, price: price }
            }
            return el
        })
        setBasePrices(changedBasePrices)

        const updatedProductPrices = productPrices.map(productPrice => {
            const updatedProducts = productPrice.products.map(product => {
                if (product.id == id) {
                    const newPrice = Number(productPrice.price) + price
                    return { ...product, price: newPrice }
                }
                return product
            })
            return {...productPrice, products: updatedProducts}
        })
        setProductPrices(updatedProductPrices) 
        setIsPriceChanged(true)
    }

    function changeProductPrices(id, price) {
        const changedProductPrices = productPrices.map(productPrice => {
            if (productPrice.id == id) {
                const products = productPrice.products.map(product => {
                    const base = basePrices.filter(el => el.id == product.id) 
                    const newPrice = Number(base[0].price) + price
                    return { ...product, price: newPrice }
                })
                return { ...productPrice, price: price, products: products }
            }
            return productPrice
        })
        setProductPrices(changedProductPrices)
        setIsPriceChanged(true)
    }

    return (
        isPriceLoading ? 
            <Loading /> : 
            <Price 
                basePrices={basePrices} 
                changeBasePrices={changeBasePrices} 
                productPrices={productPrices}
                changeProductPrices={changeProductPrices}
                isPriceChanged={isPriceChanged}
                loadPrice={loadPrice}
            /> 
    )
}

export default App