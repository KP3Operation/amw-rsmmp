/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import OverviewInpatient from "./OverviewInpatient.vue";

describe("OverviewInpatient", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(OverviewInpatient);
        expect(wrapper.exists()).toBe(true);
    });
});
