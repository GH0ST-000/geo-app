import React, {useEffect} from 'react';
import  './Modules.css'
import FirstImage from '../../assets/images/firstCardImage.jpeg';
import "aos/dist/aos.css";
import AOS from "aos";
import TomatoImage from '../../assets/images/icons8-tomato-100.png'
import CowImage from '../../assets/images/icons8-cow-64.png'
import BeeImage from '../../assets/images/bee.png'
import honeyImage from '../../assets/images/honey.jpg'
import carrotImage from '../../assets/images/carrot.jpg'
import milkImage from '../../assets/images/milk.jpg'
import {useTranslation} from "react-i18next";
import {useNavigate} from "react-router-dom";
const Modules = () => {
    const { t } = useTranslation();
    const navigate = useNavigate();
    useEffect(() => {
        AOS.init({
            duration: 1000, // Animation duration in milliseconds
            offset: 100, // Offset (in pixels) from the original trigger point
            easing: "ease-in-out", // Easing option
            once: true, // Whether animation should happen only once
        });
    }, []);


    return (
        <div className='modules-wrapper col-10 col-lg-10 mx-auto d-flex flex-column justify-content-center' style={{ minHeight: "100vh" }}>
            <h1 className='text-center modules-text p-0 m-0'>{t('ourServices')}</h1>
            <div className='card-wrapper col-12 d-flex justify-content-between gap-4'>
                {/*data-aos="fade-up"*/}
                <div className='module-card col-12 col-lg-4'>
                    <div className='card-image-wrapper'>
                        <img src={milkImage} alt={'first image'} className='card-image col-12'/>
                    </div>
                    <div className='card-content d-flex justify-content-between align-items-center gap-4 px-4'>
                        <div className='mt-4'>
                            <h3 className='card-content-title p-0 m-0'>{t('milkStandard')}</h3>
                            <p className='card-content-text pt-2 m-0'>
                                მეფუტკრეობა — სოფლის მეურნეობის დარგი, რომლის ამოცანაა ფუტკრის მოშენება.რომლის ამოცანაა
                                ფუტკრის მოშენება.
                            </p>
                        </div>
                    </div>
                    <button onClick={() => navigate('/details/milk')}
                            className='read-more-btn mt-4 mx-4 mb-4'>{t("detail")} </button>
                </div>

                <div className='module-card col-12 col-lg-4'>
                    <div className='card-image-wrapper'>
                        <img src={honeyImage} alt={'first image'} className='card-image col-12'/>
                    </div>
                    <div className='card-content d-flex justify-content-between align-items-center gap-4 px-4'>
                        <div className='mt-4'>
                            <h3 className='card-content-title p-0 m-0'>{t('honeyStandard')}</h3>
                            <p className='card-content-text pt-2 m-0'>
                                მეფუტკრეობა — სოფლის მეურნეობის დარგი, რომლის ამოცანაა ფუტკრის მოშენება. რომლის ამოცანაა
                                ფუტკრის მოშენება.
                            </p>
                        </div>
                    </div>
                    <button onClick={() => navigate('/details/honey')}
                            className='read-more-btn mt-4 mx-4 mb-4'>{t("detail")}</button>
                </div>


                <div className='module-card col-12 col-lg-4'>
                    <div className="card-image-wrapper">
                        <img src={carrotImage} alt={'first image'} className='card-image col-12'/>
                    </div>
                    <div className='card-content d-flex justify-content-between gap-4 align-items-center px-4'>
                        <div className='mt-4'>
                            <h3 className='card-content-title p-0 m-0'>{t('plantBreeding')}</h3>
                            <p className='card-content-text pt-2 m-0'>
                                მეფუტკრეობა — სოფლის მეურნეობის დარგი, რომლის ამოცანაა ფუტკრის მოშენება. რომლის ამოცანაა
                                ფუტკრის მოშენება.
                            </p>
                        </div>
                    </div>
                    <button onClick={() => navigate('/details/plant')}
                            className='read-more-btn mt-4 mx-4 mb-4'>{t("detail")} </button>
                </div>
            </div>


        </div>
    );
};

export default Modules;