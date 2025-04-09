import {useTranslation} from "react-i18next";
import FirstSection from "../../assets/images/HomeFirstSection.png";
import './Home.css'
import Modules from "../../component/Modules/Modules.jsx";
import {useNavigate} from "react-router-dom";
import {useEffect, useState} from "react";

import SuccessfulFarmers from "../../component/SuccessfulFarmers/SuccessfulFarmers.jsx";
import {fetchCategories} from "../../utils/api.js";
const Home = () => {
    const [data,setData] = useState(null);


    const { t } = useTranslation();
    const navigate = useNavigate();

    useEffect(() => {
        const getData = async () => {
            try {
                const data = await fetchCategories();
                console.log(data)
            } catch (error) {
                console.error('Error while fetching products:', error);
            }
        };
        getData()

    },[])
    return (
        <>
            <div  className="image-container">
                <div className="overlay">
                    <div className='px-3'>
                        <h1 className='ovearly-title'>რა არის გეოგაპი?</h1>
                        <p className='ovearly-text'>ქართული ადგილობრივი <br /> სტანდარტი</p>
                        <button onClick={() => navigate('/about')} className='ovearly-btn'>{t('seeMore')}</button>
                    </div>

                    {/*<img src={FirstSection} alt={'img'} className={'ovearly-image'}/>*/}

                </div>
            </div>

            <div className='divide-info-container col-8 mx-auto d-flex align-items-center justify-content-center px-4 pt-4'>
                <div className='col-12 col-sm-5'>
                    <h2 className='divide-info-title'>A step towards less pollution</h2>
                    <p className='divide-info-text'>(643)911-1633</p>
                </div>
                <div className='col-12 col-sm-7 d-flex align-items-center justify-content-center'>
                    <p className='divide-info-right-side-text'>
                        The quick, brown fox jumps over a lazy dog. DJs flock by when MTV ax quiz prog. Junk MTV quiz graced by fox whelps. Bawds jog, flick quartz, vex nymphs. Waltz, bad nymph, for quick jigs vex!


                    </p>
                </div>
            </div>

            <Modules />

            {/*<SuccessfulFarmers />*/}

        </>
    );
};

export default Home;