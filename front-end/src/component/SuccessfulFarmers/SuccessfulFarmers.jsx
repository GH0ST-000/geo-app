import React from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay } from 'swiper/modules'; // Correct Autoplay import
import 'swiper/css'; // Core Swiper CSS
import 'swiper/css/autoplay'; // Autoplay CSS (optional, but recommended)

const SuccessfulFarmers = () => {
    return (
        <div className='col-10 mx-auto mt-5 mb-5'>
            <Swiper
                spaceBetween={50}
                slidesPerView={3}
                loop={true}
                centeredSlides={true}
                autoplay={{
                    delay: 3000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: false,
                }}
                modules={[Autoplay]}
            >
                <SwiperSlide>Slide 1</SwiperSlide>
                <SwiperSlide>Slide 2</SwiperSlide>
                <SwiperSlide>Slide 3</SwiperSlide>
                <SwiperSlide>Slide 4</SwiperSlide>
                <SwiperSlide>Slide 5</SwiperSlide>
                <SwiperSlide>Slide 6</SwiperSlide>
                <SwiperSlide>Slide 7</SwiperSlide>
                <SwiperSlide>Slide 8</SwiperSlide>
            </Swiper>
        </div>
    );
};

export default SuccessfulFarmers;