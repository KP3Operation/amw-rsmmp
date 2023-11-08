/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import DoctorSchedulePage from "./DoctorSchedule.vue";

describe("DoctorSchedulePage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(DoctorSchedulePage);
        expect(wrapper.exists()).toBe(true);
    });
});
