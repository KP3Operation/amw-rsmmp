/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import DoctorScheduleDetailPage from "./DoctorScheduleDetail.vue";

describe("DoctorScheduleDetailPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(DoctorScheduleDetailPage);
        expect(wrapper.exists()).toBe(true);
    });
});
