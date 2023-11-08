/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import AppointmentDetailPage from "./AppointmentDetail.vue";

describe("AppointmentDetailPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(AppointmentDetailPage);
        expect(wrapper.exists()).toBe(true);
    });
});
